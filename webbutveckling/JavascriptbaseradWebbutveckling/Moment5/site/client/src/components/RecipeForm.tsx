import React, {ChangeEvent, useEffect, useState} from "react";
import {IngredientData, RecipeData, RecipeRequestData, TagData, Unit} from "../types";
import {requestEndpoint} from "../modules/requests";
import {IngredientDataList} from "./IngredientDataList";
import {TagDataList} from "./TagDataList";
import {UnitSelect} from "./UnitSelect";
import {useNavigate} from "react-router-dom";

let mounted = false;

export const RecipeForm = (props: { _id?: string }) => {
    const navigate = useNavigate();
    const [recipeData, setRecipeData] = useState<RecipeData>({
        _id: "",
        title: "",
        description: "",
        ingredients: [],
        instructions: [],
        tags: []
    });

    useEffect(() => {
        if (!mounted) {
            mounted = true;
            if (props._id) {
                requestEndpoint<RecipeData>(`/recipes/${props._id}`).then(async (data) => {
                    if (mounted) {
                        await setRecipeData(data);
                    }
                })
            }
        }

        return () => {
            mounted = false;
        }
    }, []);

    return (
        <form onSubmit={async e => {
            e.preventDefault();

            const requests: Promise<any>[] = [];
            recipeData.ingredients.forEach(ingredient => {
                if (!ingredient.ingredient._id) {
                    requests.push(
                        requestEndpoint<IngredientData>(
                            "/ingredients/", "POST", null, {ingredient: ingredient.ingredient.ingredient}
                        ).then(data => {
                            ingredient.ingredient._id = data._id;
                        })
                    );
                }
            });

            recipeData.tags.forEach(tag => {
                if (!tag.tag._id) {
                    requests.push(
                        requestEndpoint<TagData>(
                            "/tags/", "POST", null, {tag: tag.tag.tag}
                        ).then(data => {
                            tag.tag._id = data._id;
                        })
                    );
                }
            });

            await Promise.all(requests);
            await setRecipeData({...recipeData});

            const data: RecipeRequestData = {
                title: recipeData.title,
                description: recipeData.description,
                instructions: recipeData.instructions,
                ingredients: recipeData.ingredients.map(ingredient => {
                    return {
                        // id is guaranteed to exist after the requests above have finished.
                        ingredient: ingredient.ingredient._id as string,
                        amount: ingredient.amount,
                        unit: ingredient.unit
                    }
                }),
                tags: recipeData.tags.map(tag => {
                    return {
                        // id is guaranteed to exist after the requests above have finished.
                        tag: tag.tag._id as string
                    }
                })
            }

            if (recipeData._id) {
                await requestEndpoint<RecipeData>(
                    `/recipes/${recipeData._id}`, "PUT", null, data
                );
                navigate(`/recipes/${recipeData._id}`);
            } else {
                const response = await requestEndpoint<RecipeData>(
                    "/recipes/", "POST", null, data
                );
                navigate(`/recipes/${response._id}`);
            }
        }}>
            <div>
                <textarea value={recipeData.title} onChange={(e) => {
                    setRecipeData({...recipeData, title: e.target.value});
                }}/>
                <textarea value={recipeData.description} onChange={e => {
                    setRecipeData({...recipeData, description: e.target.value});
                }}/>
            </div>
            <div>
                <button onClick={async (e) => {
                    e.preventDefault();
                    recipeData.ingredients.push({
                        ingredient: {
                            ingredient: ""
                        },
                        amount: 0,
                        unit: "st"
                    });
                    await setRecipeData({...recipeData});
                }}>add ingredient
                </button>
                <div>
                    {
                        recipeData.ingredients.map((ingredientData, index) => {
                            const key = `${recipeData._id}-ingredients-${index}`;
                            return (<div key={key}>
                                <IngredientDataList initial={ingredientData.ingredient} event={async (data) => {

                                    ingredientData.ingredient.ingredient = data.ingredient;
                                    ingredientData.ingredient._id = data._id;
                                    setRecipeData({...recipeData});
                                }}/>
                                <input type={"number"} value={ingredientData.amount}
                                       onChange={e => {
                                           const amount = parseInt(e.target.value)
                                           ingredientData.amount = amount >= 0 ? amount : 0;
                                           setRecipeData({...recipeData});

                                       }}/>
                                <UnitSelect value={ingredientData.unit}
                                            event={(e: ChangeEvent<HTMLSelectElement>) => {
                                                ingredientData.unit = e.target.value as Unit;
                                                setRecipeData({...recipeData});
                                            }}/>
                            </div>)
                        })
                    }
                </div>

            </div>
            <div>
                {
                    recipeData.instructions.map((instructionData, index) => {
                        return <textarea key={`${recipeData._id}-instruction-${index}`} value={instructionData}
                                         onChange={e => {
                                             recipeData.instructions[index] = e.target.value;
                                             setRecipeData(recipeData);
                                         }}/>
                    })
                }
            </div>
            <div>
                <button onClick={async e => {
                    e.preventDefault();
                    recipeData.tags.push({
                        tag: {
                            tag: ""
                        }
                    })
                    await setRecipeData({...recipeData});
                }}
                >add tag
                </button>
                {
                    recipeData.tags.map((tagData, index) => {
                        const key = `${recipeData._id}-ingredients-${index}-name`;
                        return <TagDataList key={key} initial={tagData.tag} event={(data) => {
                            tagData.tag.tag = data.tag;
                            tagData.tag._id = data._id;
                            setRecipeData({...recipeData});
                        }}/>
                    })
                }
            </div>
            <input type={"submit"} value={"apply"}/>
        </form>
    );
}
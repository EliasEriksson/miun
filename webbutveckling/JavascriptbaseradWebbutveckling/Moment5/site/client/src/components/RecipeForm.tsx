import React, {ChangeEvent, useEffect, useState} from "react";
import {RecipeData, Unit} from "../types";
import {requestEndpoint} from "../modules/requests";
import {IngredientDataList} from "./IngredientDataList";
import {TagDataList} from "./TagDataList";
import {UnitSelect} from "./UnitSelect";

let mounted = false;

export const RecipeForm = (props: { _id?: string }) => {
    const [loaded, setLoaded] = useState<boolean>(false);
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
                requestEndpoint<RecipeData>(`/recipes/${props._id}`).then(async ([data, status]) => {
                    if (200 <= status && status < 300 && mounted) {
                        await setRecipeData(data);
                    }
                })
                setLoaded(true);
            } else {
                setLoaded(true);
            }
        }

        return () => {
            mounted = false;
        }
    }, []);
    return (
        <form onSubmit={e => e.preventDefault()}>
            <input type={"hidden"} value={recipeData._id}/>
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
                    recipeData.ingredients.push({
                        _id: "",
                        ingredient: {
                            _id: "",
                            ingredient: ""
                        },
                        amount: 0,
                        unit: "st"
                    });
                    await setRecipeData({...recipeData});
                }}>add ingredient
                </button>
                {
                    recipeData.ingredients.map((ingredientData) => {
                        return (<div key={ingredientData._id}>
                            <input type={"hidden"} value={ingredientData.ingredient._id}/>
                            <IngredientDataList initial={ingredientData.ingredient} event={(data) => {
                                ingredientData.ingredient.ingredient = data.ingredient;
                                ingredientData.ingredient._id = data._id;
                                setRecipeData({...recipeData});
                            }}/>
                            <input type={"number"} value={ingredientData.amount} onChange={e => {
                                const amount = parseInt(e.target.value)
                                ingredientData.amount = amount >= 0 ? amount : 0;
                                setRecipeData({...recipeData});

                            }}/>
                            <UnitSelect name={"unit"} value={ingredientData.unit}
                                        event={(e: ChangeEvent<HTMLSelectElement>) => {
                                            ingredientData.unit = e.target.value as Unit;
                                            setRecipeData({...recipeData});
                                        }}/>
                        </div>)
                    })
                }
            </div>
            <div>
                {
                    recipeData.instructions.map((instructionData, index) => {
                        return <textarea key={`${recipeData._id}-${index}`} value={instructionData} onChange={e => {
                            recipeData.instructions[index] = e.target.value;
                            setRecipeData(recipeData);
                        }}/>
                    })
                }
            </div>
            <div>
                <button onClick={async e => {
                    recipeData.tags.push({
                        _id: "",
                        tag: {
                            _id: "",
                            tag: ""
                        }
                    })
                    await setRecipeData({...recipeData});
                }}
                >add tag</button>
                {
                    recipeData.tags.map(tagData => {
                        return <TagDataList key={tagData._id} initial={tagData.tag} event={(data) => {
                            tagData.tag.tag = data.tag;
                            tagData.tag._id = data._id
                        }}/>
                    })
                }
            </div>
        </form>
    );
}
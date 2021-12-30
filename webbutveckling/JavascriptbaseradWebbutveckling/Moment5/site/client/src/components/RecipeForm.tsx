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
        <form onSubmit={event => {
        }}>
            <input type={"hidden"} value={recipeData._id}/>
            <textarea value={recipeData.title} onChange={(e) => {
                setRecipeData({...recipeData, title: e.target.value});
            }}/>
            <textarea value={recipeData.description} onChange={e => {
                setRecipeData({...recipeData, description: e.target.value});
            }}/>
            <div>
                <button onClick={(e) => {

                }}>add ingredient
                </button>
            </div>

            {
                recipeData.ingredients.map((ingredientData) => {
                    return (<div key={ingredientData._id}>
                        <input type={"hidden"} value={ingredientData.ingredient._id}/>
                        <IngredientDataList current={ingredientData.ingredient} setIngredient={(data) => {
                            ingredientData.ingredient.ingredient = data.ingredient;
                            ingredientData.ingredient._id = data._id;
                        }}/>
                        <input type={"number"} value={ingredientData.amount} onChange={e => {
                            ingredientData.amount = parseInt(e.target.value);
                        }}/>
                        <UnitSelect name={"unit"} setUnit={(e: ChangeEvent<HTMLSelectElement>) => {
                            ingredientData.unit = e.target.value as Unit;
                        }}/>
                    </div>)
                })
            }
            {
                recipeData.instructions.map((instructionData, index) => {
                    return <textarea key={`${recipeData._id}-${index}`} value={instructionData} onChange={e => {
                        recipeData.instructions[index] = e.target.value;
                        setRecipeData(recipeData);
                    }}/>
                })
            }
            {
                recipeData.tags.map(tagData => {
                    return <TagDataList key={tagData._id} _id={tagData._id} setTag={(data) => {
                        tagData.tag.tag = data.tag;
                        tagData.tag._id = data._id
                    }}/>
                })
            }
        </form>
    );
}
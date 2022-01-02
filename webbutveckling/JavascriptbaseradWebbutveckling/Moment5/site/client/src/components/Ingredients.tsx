import React, {ChangeEvent, Dispatch, SetStateAction} from "react";
import {RecipeData, Unit} from "../types";
import {IngredientDataList} from "./IngredientDataList";
import {UnitSelect} from "./UnitSelect";
import {v4 as uuid} from "uuid";

interface ParentState {
    recipeData: RecipeData
}

export const Ingredients: React.FC<{
    parentState: ParentState,
    parentSetState: Dispatch<SetStateAction<ParentState>>
}> = (props) => {
    return (
        <div>
            <button onClick={async e => {
                e.preventDefault();
                props.parentState.recipeData.ingredients.push({
                    ingredient: {
                        ingredient: "",
                        key: uuid()
                    },
                    amount: 0,
                    unit: "st",
                    key: uuid()
                });
                await props.parentSetState({...props.parentState});
            }}>
                add ingredient
            </button>
            <div>
                {
                    props.parentState.recipeData.ingredients.map((ingredientData, index) => (
                            <div key={ingredientData.key ?? ingredientData._id}>
                                <IngredientDataList initial={ingredientData.ingredient} event={async data => {
                                    ingredientData.ingredient.ingredient = data.ingredient;
                                    ingredientData.ingredient._id = data._id;
                                    props.parentSetState({...props.parentState});
                                }}/>
                                <input type={"number"} value={ingredientData.amount}
                                       onChange={e => {
                                           const amount = parseInt(e.target.value)
                                           ingredientData.amount = amount >= 0 ? amount : 0;
                                           props.parentSetState({...props.parentState});

                                       }}/>
                                <UnitSelect value={ingredientData.unit}
                                            event={(e: ChangeEvent<HTMLSelectElement>) => {
                                                ingredientData.unit = e.target.value as Unit;
                                                props.parentSetState({...props.parentState});
                                            }}/>
                                <button onClick={async e => {
                                    e.preventDefault();
                                    props.parentState.recipeData.ingredients.splice(index, 1);
                                    await props.parentSetState({...props.parentState});
                                }}>
                                    X
                                </button>
                            </div>
                        )
                    )
                }
            </div>
        </div>
    );
}
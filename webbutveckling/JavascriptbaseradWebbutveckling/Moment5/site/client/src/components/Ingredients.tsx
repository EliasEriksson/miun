import React, {ChangeEvent, Dispatch, SetStateAction} from "react";
import {RecipeData, Unit} from "../types";
import {IngredientDataList} from "./IngredientDataList";
import {UnitSelect} from "./UnitSelect";
import {v4 as uuid} from "uuid";
import {ReactComponent as TrashCan} from "../media/assests/delete.svg";

interface ParentState {
    recipeData: RecipeData,
    error: string
}

/**
 * component for ingredients in the recipe form
 *
 * @param props
 * @constructor
 */
export const Ingredients: React.FC<{
    parentState: ParentState,
    parentSetState: Dispatch<SetStateAction<ParentState>>
}> = (props) => {
    return (
        <div className={"ingredients-container wrapper"}>
            <div className={"ingredients"}>
                {
                    props.parentState.recipeData.ingredients.map((ingredientData, index) => (
                            <div className={"ingredient"} key={ingredientData.key ?? ingredientData._id}>
                                <IngredientDataList initial={ingredientData.ingredient} event={async data => {
                                    ingredientData.ingredient.ingredient = data.ingredient;
                                    ingredientData.ingredient._id = data._id;
                                    props.parentSetState({...props.parentState});
                                }}/>
                                <label htmlFor={(ingredientData.key ?? ingredientData._id) + "-amount-input"}>
                                    Amount:
                                </label>
                                <input id={(ingredientData.key ?? ingredientData._id) + "-amount-input"} type={"number"}
                                       value={`${parseFloat(`${ingredientData.amount}`) ?? 0}`}
                                       onChange={e => {
                                           const amount = parseFloat(e.target.value)
                                           ingredientData.amount = amount >= 0 ? amount : 0;
                                           props.parentSetState({...props.parentState});
                                       }} onKeyPress={e => {
                                    if (e.key === "Enter") e.preventDefault()
                                }}/>


                                <UnitSelect ingredientData={ingredientData}
                                            event={(e: ChangeEvent<HTMLSelectElement>) => {
                                                ingredientData.unit = e.target.value as Unit;
                                                props.parentSetState({...props.parentState});
                                            }}/>
                                <button className={"delete"} onClick={async e => {
                                    console.log(e.target);
                                    props.parentState.recipeData.ingredients.splice(index, 1);
                                    await props.parentSetState({...props.parentState});
                                }}>
                                    <TrashCan/>
                                </button>
                            </div>
                        )
                    )
                }
            </div>
            <button className={"add-button"} onClick={async e => {
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
                Add Ingredient
            </button>
        </div>
    );
}
import React, {ChangeEvent, useState} from "react";
import "../static/css/unitSelect.scss";
import {RecipeIngredientData, RecipeData, Unit} from "../types";

/**
 * a component for selecting units for the recipe form.
 * @param props
 * @constructor
 */
export const UnitSelect = (props: { ingredientData: RecipeIngredientData, event: (e: ChangeEvent<HTMLSelectElement>) => void }) => {
    const [state, setState] = useState<{
        ingredientData: RecipeIngredientData
    }>({
        ingredientData: props.ingredientData
    });
    const identifier = props.ingredientData.key || props.ingredientData._id;
    return (
        <>
            <label htmlFor={identifier + "-unit-input"}>Unit:</label>
            <select id={identifier + "-unit-input"} className={"unit-select"} value={state.ingredientData.unit} onChange={async e => {
                state.ingredientData.unit = e.target.value as Unit;
                setState({...state});
                props.event(e);
            }}>
                <option value={"ml"}>ml</option>
                <option value={"cl"}>cl</option>
                <option value={"dl"}>dl</option>
                <option value={"l"}>l</option>
                <option value={"g"}>g</option>
                <option value={"kg"}>kg</option>
                <option value={"st"}>st</option>
            </select>
        </>
    );
}
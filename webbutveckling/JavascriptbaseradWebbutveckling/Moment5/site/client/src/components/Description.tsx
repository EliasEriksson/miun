import React, {Dispatch, SetStateAction} from "react";
import {RecipeData} from "../types";

interface ParentState {
    recipeData: RecipeData
}

export const Description: React.FC<{
    parentState: ParentState,
    parentSetState: Dispatch<SetStateAction<ParentState>>
}> = props => {
    return (
        <label>
            Description:
            <textarea value={props.parentState.recipeData.description} onChange={async e => {
                props.parentState.recipeData.description = e.target.value;
                await props.parentSetState({...props.parentState});
            }}/>
        </label>
    )
}
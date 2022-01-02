import React, {Dispatch, SetStateAction} from "react";
import {RecipeData} from "../types";

interface ParentState {
    recipeData: RecipeData
}

export const Title: React.FC<{
    parentState: ParentState,
    parentSetState: Dispatch<SetStateAction<ParentState>>
}> = props => {
    return (
        <label>
            Title:
            <textarea value={props.parentState.recipeData.title} onChange={async e => {
                props.parentState.recipeData.title = e.target.value;
                await props.parentSetState({...props.parentState});
            }}/>
        </label>
    );
}
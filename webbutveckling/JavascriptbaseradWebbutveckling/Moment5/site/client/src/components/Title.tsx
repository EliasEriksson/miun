import React, {Dispatch, SetStateAction, useEffect, useRef} from "react";
import {RecipeData} from "../types";
import {autoGrow} from "../modules/triggers";

interface ParentState {
    recipeData: RecipeData,
    error: string
}

/**
 * renders a recipes title.
 */
export const Title: React.FC<{
    parentState: ParentState,
    parentSetState: Dispatch<SetStateAction<ParentState>>
}> = props => {
    const textArea = useRef(null);

    useEffect(() => {
        if (textArea.current) {
            autoGrow(textArea.current);
        }
    }, [])
    return (
        <label className={"title"}>
            Title:
            <textarea ref={textArea} value={props.parentState.recipeData.title} onChange={async e => {
                props.parentState.recipeData.title = e.target.value;
                await props.parentSetState({...props.parentState});
            }}/>
        </label>
    );
}
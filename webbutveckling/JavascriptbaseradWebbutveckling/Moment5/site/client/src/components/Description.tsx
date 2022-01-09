import React, {Dispatch, SetStateAction, useEffect, useRef} from "react";
import {RecipeData} from "../types";
import {autoGrow} from "../modules/triggers";

interface ParentState {
    recipeData: RecipeData,
    error: string
}

/**
 * renders a recipes description.
 */
export const Description: React.FC<{
    parentState: ParentState,
    parentSetState: Dispatch<SetStateAction<ParentState>>
}> = props => {
    const textArea = useRef<HTMLTextAreaElement>(null)

    /*
     * if the text area rendered on the screen
     * make the textarea responsive
     */
    useEffect(() => {
        if (textArea.current) {
            autoGrow(textArea.current)
        }
    },[]);

    return (
        <label className={"description"}>
            Description:
            <textarea ref={textArea} value={props.parentState.recipeData.description} onChange={async e => {
                props.parentState.recipeData.description = e.target.value;
                await props.parentSetState({...props.parentState});
            }}/>
        </label>
    )
}
import React, {Dispatch, SetStateAction, useEffect, useRef} from "react";
import {RecipeData} from "../types";
import {autoGrow} from "../modules/triggers";

interface ParentState {
    recipeData: RecipeData
}

export const Description: React.FC<{
    parentState: ParentState,
    parentSetState: Dispatch<SetStateAction<ParentState>>
}> = props => {
    const textArea = useRef<HTMLTextAreaElement>(null)
    useEffect(() => {
        if (textArea.current) {
            autoGrow(textArea.current)
        }
    },[]);
    return (
        <label className={"description"}>
            Description:
            <textarea ref={textArea} value={props.parentState.recipeData.description} onLoad={async e => {
                console.log("hello")
                console.log(e);
            }} onChange={async e => {
                props.parentState.recipeData.description = e.target.value;
                await props.parentSetState({...props.parentState});
            }}/>
        </label>
    )
}
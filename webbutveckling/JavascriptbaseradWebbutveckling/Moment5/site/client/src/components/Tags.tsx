import React, {Dispatch, SetStateAction} from "react";
import {RecipeData} from "../types";
import {TagDataList} from "./TagDataList";
import {v4 as uuid} from "uuid";

interface ParentState {
    recipeData: RecipeData
}

export const Tags: React.FC<{
    parentState: ParentState,
    parentSetState: Dispatch<SetStateAction<ParentState>>
}> = (props) => {
    return (
        <div>
            <button onClick={async e => {
                e.preventDefault();
                props.parentState.recipeData.tags.push({
                    tag: {
                        tag: "",
                        key: uuid()
                    },
                    key: uuid()
                })
                props.parentSetState({...props.parentState});
            }}>
                add tag
            </button>
            <div>
                {
                    props.parentState.recipeData.tags.map((tagData, index) =>
                        <div key={tagData.key ?? tagData._id}>
                            <TagDataList initial={tagData.tag} event={data => {
                                tagData.tag.tag = data.tag;
                                tagData.tag._id = data._id;
                                props.parentSetState({...props.parentState});
                            }}/>
                            <button onClick={async e => {
                                e.preventDefault();
                                props.parentState.recipeData.tags.splice(index, 1);
                                props.parentSetState({...props.parentState});
                            }}>
                                X
                            </button>
                        </div>
                    )
                }
            </div>
        </div>
    );
}
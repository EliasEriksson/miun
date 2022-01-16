import React, {Dispatch, SetStateAction} from "react";
import {RecipeData} from "../types";
import {TagDataList} from "./TagDataList";
import {v4 as uuid} from "uuid";
import {ReactComponent as TrashCan} from "../media/assests/delete.svg";

interface ParentState {
    recipeData: RecipeData,
    error: string
}

/**
 * component for tags in the recipe form
 *
 * @param props
 * @constructor
 */
export const Tags: React.FC<{
    parentState: ParentState,
    parentSetState: Dispatch<SetStateAction<ParentState>>
}> = (props) => {
    return (
        <div className={"wrapper"}>
            <div className={"tags"}>
                {
                    props.parentState.recipeData.tags.map((tagData, index) =>
                        <div className={"tag"} key={tagData.key ?? tagData._id}>
                            <TagDataList initial={tagData.tag} event={data => {
                                tagData.tag.tag = data.tag;
                                tagData.tag._id = data._id;
                                props.parentSetState({...props.parentState});
                            }}/>
                            <button className={"delete"} onClick={async e => {
                                e.preventDefault();
                                props.parentState.recipeData.tags.splice(index, 1);
                                props.parentSetState({...props.parentState});
                            }}>
                                <TrashCan/>
                            </button>
                        </div>
                    )
                }
            </div>
            <button className={"add-button"} onClick={async e => {
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
                Add Tag
            </button>
        </div>
    );
}
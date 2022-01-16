import React, {Dispatch, SetStateAction, useEffect, useRef} from "react";
import {RecipeData} from "../types";
import {v4 as uuid} from "uuid";
import {ReactComponent as ArrowUp} from "../media/assests/up.svg";
import {ReactComponent as ArrowDown} from "../media/assests/down.svg";
import {ReactComponent as TrashCan} from "../media/assests/delete.svg";
import {autoGrow} from "../modules/triggers";

interface ParentState {
    recipeData: RecipeData,
    error: string
}

/**
 * helper method to apply auto grow on text areas
 *
 * @param element
 * @param identifier
 * @param refs
 */
const addAutoGrow = (element: HTMLTextAreaElement | null, identifier: string, refs: {
    addedTextAreas: [HTMLTextAreaElement, string][], processedTextAreas: Set<string>
}) => {
    if (element && !refs.processedTextAreas.has(identifier)) {
        autoGrow(element);
        refs.processedTextAreas.add(identifier);
    }
}

/**
 * component for instructions in the recipe form
 *
 * @param props
 * @constructor
 */
export const Instructions: React.FC<{
    parentState: ParentState,
    parentSetState: Dispatch<SetStateAction<ParentState>>
}> = (props) => {
    const refs = useRef<{
        addedTextAreas: [HTMLTextAreaElement, string][],
        processedTextAreas: Set<string>
    }>({
        addedTextAreas: [],
        processedTextAreas: new Set()
    })

    useEffect(() => {
        return () => {
            refs.current.addedTextAreas = [];
            refs.current.processedTextAreas = new Set();
        }
    }, [])

    return (
        <div className={"wrapper"}>
            <div className={"instructions"}>
                {
                    props.parentState.recipeData.instructions.map((data, index) => (
                        <div className={"instruction"} key={data.key ?? data._id}>
                            <label htmlFor={data.key ?? data._id}>
                                Step: {index + 1}
                            </label>
                            <textarea id={data.key ?? data._id} ref={el => addAutoGrow(el, (data.key ?? data._id) as string, refs.current)}
                                      value={data.instruction}
                                      onChange={async e => {
                                          data.instruction = e.target.value;
                                          props.parentSetState({...props.parentState});
                                      }}/>
                            <div className={"controls"}>
                                {index > 0 ? <button className={"move-up"} onClick={async e => {
                                    e.preventDefault();
                                    if (index > 0) {
                                        props.parentState.recipeData.instructions.splice(
                                            index - 1, 0,
                                            props.parentState.recipeData.instructions.splice(index, 1)[0]
                                        );
                                        await props.parentSetState({...props.parentState});
                                    }
                                }}>
                                    <ArrowUp/>
                                </button> : null}
                                <button className={"delete"} onClick={async e => {
                                    e.preventDefault();
                                    props.parentState.recipeData.instructions.splice(index, 1);
                                    await props.parentSetState({...props.parentState});
                                }}>
                                    <TrashCan/>
                                </button>
                                {index + 1 < props.parentState.recipeData.instructions.length ?
                                    <button className={"move-down"} onClick={async e => {
                                        e.preventDefault();
                                        if (index + 1 < props.parentState.recipeData.instructions.length) {
                                            props.parentState.recipeData.instructions.splice(
                                                index + 1, 0,
                                                props.parentState.recipeData.instructions.splice(index, 1)[0]
                                            );
                                            await props.parentSetState({...props.parentState});
                                        }
                                    }}>
                                        <ArrowDown/>
                                    </button> : null
                                }
                            </div>
                        </div>
                    ))
                }
            </div>
            <button className={"add-button"} onClick={async e => {
                e.preventDefault();
                props.parentState.recipeData.instructions.push({
                    instruction: "",
                    key: uuid()
                });
                props.parentSetState({...props.parentState});
            }}>
                Add Instruction
            </button>
        </div>
    )
}
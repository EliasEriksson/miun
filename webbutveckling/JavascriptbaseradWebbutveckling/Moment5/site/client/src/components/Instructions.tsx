import React, {Dispatch, SetStateAction} from "react";
import {RecipeData} from "../types";
import {v4 as uuid} from "uuid";

interface ParentState {
    recipeData: RecipeData
}

export const Instructions: React.FC<{
    parentState: ParentState,
    parentSetState: Dispatch<SetStateAction<ParentState>>
}> = (props) => {
    return (
        <div>
            <button onClick={async e => {
                e.preventDefault();
                props.parentState.recipeData.instructions.push({
                    instruction: "",
                    key: uuid()
                });
                props.parentSetState({...props.parentState});
            }}>
                add instruction
            </button>
            <div>
                {
                    props.parentState.recipeData.instructions.map((data, index) => (
                        <div key={data.key ?? data._id}>
                            <textarea value={data.instruction} onChange={async e => {
                                data.instruction = e.target.value;
                                props.parentSetState({...props.parentState});
                            }}/>
                            <div>
                                {index > 0 ?
                                    <button onClick={async e => {
                                        e.preventDefault();
                                        if (index > 0) {
                                            props.parentState.recipeData.instructions.splice(
                                                index - 1, 0,
                                                props.parentState.recipeData.instructions.splice(index, 1)[0]
                                            );
                                            await props.parentSetState({...props.parentState});
                                        }
                                    }}>
                                        Ʌ
                                    </button> : null
                                }
                                <button onClick={async e => {
                                    e.preventDefault();
                                    props.parentState.recipeData.instructions.splice(index, 1);
                                    await props.parentSetState({...props.parentState});
                                }}>
                                    X
                                </button>
                                {index + 1 < props.parentState.recipeData.instructions.length ?
                                    <button onClick={async e => {
                                        e.preventDefault();
                                        if (index + 1 < props.parentState.recipeData.instructions.length) {
                                            props.parentState.recipeData.instructions.splice(
                                                index + 1, 0,
                                                props.parentState.recipeData.instructions.splice(index, 1)[0]
                                            );
                                            await props.parentSetState({...props.parentState});
                                        }
                                    }}>
                                        V
                                    </button> : null
                                }
                            </div>
                        </div>
                    ))
                }
            </div>
        </div>
    )
}
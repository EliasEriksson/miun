import React, {SetStateAction, useEffect, useState} from "react";
import {IngredientData, RecipeData, RecipeRequestData, TagData} from "../types";
import {requestEndpoint} from "../modules/requests";
import {NavigateFunction, useNavigate} from "react-router-dom";
import {Ingredients} from "./Ingredients";
import {Tags} from "./Tags";
import {Title} from "./Title";
import {Description} from "./Description";
import {Instructions} from "./Instructions";
import {v4 as uuid} from "uuid";
import "../static/css/recipeForm.scss";


interface State {
    recipeData: RecipeData,
    error: string
}

const handleSubmit = async (state: State, setState: React.Dispatch<SetStateAction<State>>, navigate: NavigateFunction) => {
    const requests: Promise<any>[] = [];
    state.recipeData.ingredients.forEach(ingredient => {
        if (!ingredient.ingredient._id) {
            requests.push(
                requestEndpoint<IngredientData>(
                    "/ingredients/", "POST", null, {ingredient: ingredient.ingredient.ingredient}
                ).then(data => {
                    ingredient.ingredient._id = data._id;
                })
            );
        }
    });

    state.recipeData.tags.forEach(tag => {
        if (!tag.tag._id) {
            requests.push(
                requestEndpoint<TagData>(
                    "/tags/", "POST", null, {tag: tag.tag.tag}
                ).then(data => {
                    tag.tag._id = data._id;
                })
            );
        }
    });

    try {
        await Promise.all(requests);

        let data: RecipeRequestData = {
            title: state.recipeData.title,
            description: state.recipeData.description,
            instructions: state.recipeData.instructions.map(instruction => ({instruction: instruction.instruction})),
            ingredients: state.recipeData.ingredients.map(ingredient => {
                return {
                    // id is guaranteed to exist after the requests above have finished.
                    ingredient: ingredient.ingredient._id as string,
                    amount: ingredient.amount,
                    unit: ingredient.unit
                }
            }),
            tags: state.recipeData.tags.map(tag => {
                return {
                    // id is guaranteed to exist after the requests above have finished.
                    tag: tag.tag._id as string
                }
            })
        };

        if (state.recipeData._id) {
            data._id = state.recipeData._id;
            await requestEndpoint<RecipeData>(
                `/recipes/${state.recipeData._id}`, "PUT", null, data
            );
            navigate(`/recipes/${state.recipeData._id}`);
        } else {
            const response = await requestEndpoint<RecipeData>(
                "/recipes/", "POST", null, data
            );
            navigate(`/recipes/${response._id}`);
        }
        state.error = "";
    } catch (e: any) {
        handleSubmitError(JSON.parse(e.message), state);
    }
    await setState({...state});
}

const handleDelete = async (state: State, navigate: NavigateFunction) => {
    if (window.confirm(`Are you sure you want to delete recipe '${state.recipeData.title}'?`)) {
        await requestEndpoint(`/recipes/${state.recipeData._id}/`, "DELETE", null);
        navigate("/");
    }
}


const handleSubmitError = (error: { [key: string]: { kind: string, path: string }}, state: State) => {
    console.log(error)
    for (const key of Object.keys(error)) {
        const field = error[key].path.split(".")[0]
        state.error = `${field.charAt(0).toUpperCase() + field.substring(1)} has at least one empty field.`;
        break;
    }
}


export const RecipeForm: React.FC<{
    _id?: string
}> = props => {
    const navigate = useNavigate();
    const [state, setState] = useState<State>({
        recipeData: {
            title: "",
            description: "",
            ingredients: [],
            instructions: [],
            tags: [],
            key: uuid()
        },
        error: ""
    });
    useEffect(() => {
        if (props._id) {
            requestEndpoint<RecipeData>(`/recipes/${props._id}`).then(async (data) => {
                state.recipeData = data;
                await setState({...state});
            })
        }

        return () => {
        }
    }, []);
    return (
        <form className={"recipe-form"} onSubmit={async e => e.preventDefault()
        }>
            <Title parentState={state} parentSetState={setState}/>
            <hr/>
            <Description parentState={state} parentSetState={setState}/>
            <hr/>
            <Ingredients parentState={state} parentSetState={setState}/>
            <hr/>
            <Instructions parentState={state} parentSetState={setState}/>
            <hr/>
            <Tags parentState={state} parentSetState={setState}/>
            <hr/>
            {state.error ? (
                <>
                    <p className={"error"}>{state.error}</p>
                    <hr/>
                </>
            ) : null}

            <div className={"wrapper buttons"}>
                <input className={"submit-button"} type={"submit"} value={"Apply"} onClick={async e => {
                    e.preventDefault();
                    await handleSubmit(state, setState, navigate);
                }}/>
                <input className={"delete-button"} type={"submit"} value={"Delete"} onClick={async e => {
                    e.preventDefault();
                    await handleDelete(state, navigate)
                }}/>
            </div>
        </form>
    );
}
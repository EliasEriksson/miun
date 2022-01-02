import React, {SetStateAction, useEffect, useRef, useState} from "react";
import {IngredientData, RecipeData, RecipeRequestData, TagData} from "../types";
import {requestEndpoint} from "../modules/requests";
import {NavigateFunction, useNavigate} from "react-router-dom";
import {Ingredients} from "./Ingredients";
import {Tags} from "./Tags";
import {Title} from "./Title";
import {Description} from "./Description";
import {Instructions} from "./Instructions";
import {v4 as uuid} from "uuid";


let mounted = false;

interface State {
    recipeData: RecipeData
}

interface Attributes {

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

    await Promise.all(requests);
    await setState({...state});

    const data: RecipeRequestData = {
        title: state.recipeData.title,
        description: state.recipeData.description,
        instructions: state.recipeData.instructions,
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
}


export const RecipeForm: React.FC<{
    _id?: string
}> = props => {
    const navigate = useNavigate();
    const attributes = useRef<Attributes>({

    });
    const [state, setState] = useState<State>({
        recipeData: {
            _id: "",
            title: "",
            description: "",
            ingredients: [],
            instructions: [],
            tags: [],
            key: uuid()
        }
    });
    useEffect(() => {
        if (!mounted) {
            mounted = true;
            if (props._id) {
                requestEndpoint<RecipeData>(`/recipes/${props._id}`).then(async (data) => {
                    if (mounted) {
                        state.recipeData = data;
                        await setState({...state});
                    }
                })
            }
        }

        return () => {
            attributes.current = {};
            mounted = false;
        }
    }, []);
    return (
        <form onSubmit={async e => {
            e.preventDefault();
            await handleSubmit(state, setState, navigate);
        }}>
            <Title parentState={state} parentSetState={setState}/>
            <Description parentState={state} parentSetState={setState}/>
            <Ingredients parentState={state} parentSetState={setState}/>
            <Instructions parentState={state} parentSetState={setState}/>
            <Tags parentState={state} parentSetState={setState}/>
            <input type={"submit"} value={"apply"}/>
        </form>
    );
}
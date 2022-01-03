import React, {useState, useEffect} from "react";
import {Link, useParams} from "react-router-dom";
import {Loader} from "./Loader";
import {requestEndpoint} from "../modules/requests";
import {RecipeData} from "../types";
import {v4 as uuid} from "uuid";

let mounted = false;

interface State {
    recipeData: RecipeData
}

export const ViewRecipe = () => {
    const params = useParams();
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
        mounted = true;
        requestEndpoint<RecipeData>(`/recipes/${params._id}`, "GET", null, undefined).then(async data => {
            state.recipeData = data;
            await setState({...state});
        })

    }, []);

    return (
        <main>
            <div>
                <Link to={`/recipes/edit/${state.recipeData._id}`}>Edit</Link>
                <h2>{state.recipeData.title}</h2>
                <p>{state.recipeData.description}</p>
                <ol>
                    {state.recipeData.ingredients.map(data => (
                        <li key={data.key ?? data._id}>
                            {data.ingredient.ingredient} {data.amount} {data.unit}
                        </li>
                    ))}
                </ol>
                <ol>
                    {state.recipeData.instructions.map(data => (
                        <li key={data.key ?? data._id}>
                            {data.instruction}
                        </li>
                    ))}
                </ol>
                <ul>
                    {state.recipeData.tags.map(data => (
                        <li key={data.key ?? data._id}>
                            {data.tag.tag}
                        </li>
                    ))}
                </ul>
            </div>
            {!state.recipeData._id ? <Loader/> : null}
        </main>
    );
}

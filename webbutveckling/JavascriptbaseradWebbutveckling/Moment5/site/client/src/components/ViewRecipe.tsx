import React, {useState, useEffect} from "react";
import {Link, useParams} from "react-router-dom";
import {Loader} from "./Loader";
import {requestEndpoint} from "../modules/requests";
import {RecipeData} from "../types";

let mounted = false;

interface State {
    page: JSX.Element | null
}

export const ViewRecipe = () => {
    const params = useParams();
    const [state, setState] = useState<State>({
        page: null
    });

    // TODO MOVE ALL OF THIS CRAP INTO THE RENDER
    useEffect(() => {
        if (!mounted) {
            mounted = true;
            requestEndpoint<RecipeData>(`/recipes/${params._id}`, "GET", null, undefined).then(async (recipeData) => {
                if (mounted) {
                    state.page = (
                        <div>
                            <Link to={`/recipes/edit/${recipeData._id}`}>Edit</Link>
                            <h2>{recipeData.title}</h2>
                            <p>{recipeData.description}</p>
                            <ol>
                                {recipeData.ingredients.map(data => (
                                    <li key={data.key}>
                                        {data.ingredient.ingredient} {data.amount} {data.unit}
                                    </li>
                                ))}
                            </ol>
                            <ol>
                                {recipeData.instructions.map(data => (
                                    <li key={data.key}>
                                        {data.instruction}
                                    </li>
                                ))}
                            </ol>
                            <ul>
                                {recipeData.tags.map(data => (
                                    <li key={data.key}>
                                        {data.tag.tag}
                                    </li>
                                ))}
                            </ul>
                        </div>
                    );
                    await setState({...state});
                }
            })
        }
        return function () {
            mounted = false
        }
    }, []);

    return (
        <main>
            {state.page}
            {!state.page ? <Loader/> : null}
        </main>
    );
}

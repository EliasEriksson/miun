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
    // const [page, setPage] = useState<null | JSX.Element>(null);

    useEffect(() => {
        if (!mounted) {
            mounted = true;
            requestEndpoint<RecipeData>(`/recipes/${params._id}`, "GET", null, undefined).then(async (data) => {
                if (mounted) {
                    state.page = (
                        <div>
                            <Link to={`/recipes/edit/${data._id}`}>Edit</Link>
                            <h2>{data.title}</h2>
                            <p>{data.description}</p>
                            <ol>
                                {data.ingredients.map(recipeIngredient => (
                                    <li key={recipeIngredient._id}>
                                        {recipeIngredient.ingredient.ingredient} {recipeIngredient.amount} {recipeIngredient.unit}
                                    </li>
                                ))}
                            </ol>
                            <ol>
                                {data.instructions.map((instruction, index) => (
                                    <li key={`${data._id}-instruction-${index}`}>
                                        {instruction}
                                    </li>
                                ))}
                            </ol>
                            <ul>
                                {data.tags.map(tagData => (
                                    <li key={tagData._id}>
                                        {tagData.tag.tag}
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

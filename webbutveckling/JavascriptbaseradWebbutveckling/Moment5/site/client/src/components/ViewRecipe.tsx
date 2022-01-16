import React, {useState, useEffect} from "react";
import {Link, useParams} from "react-router-dom";
import {Loader} from "./Loader";
import {requestEndpoint} from "../modules/requests";
import {RecipeData} from "../types";
import {v4 as uuid} from "uuid";
import "../static/css/viewRecipe.scss";

let mounted = false;

interface State {
    recipeData: RecipeData
}

/**
 * renders a recipe with all related data
 *
 * @constructor
 */
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
        <div className={"recipe"}>
            <nav>
                <Link to={"/"}>Go back</Link>
                <Link to={`/recipes/edit/${state.recipeData._id}`}>Edit</Link>
            </nav>

            <h2>{state.recipeData.title}</h2>
            <p>{state.recipeData.description}</p>
            <ul className={"tags"}>
                {state.recipeData.tags.map(data => (
                    <li className={"tag"} key={data.key ?? data._id}>
                        {data.tag.tag}
                    </li>
                ))}
            </ul>
            <div className={"columns"}>
                {state.recipeData.ingredients.length ? (
                    <div className={"ingredients-wrapper"}>
                        <h3>Ingredients</h3>
                        <ul className={"ingredients"}>
                            {state.recipeData.ingredients.map(data => (
                                <li key={data.key ?? data._id}>
                                    <span>{data.ingredient.ingredient}</span> {data.amount} {data.unit}
                                </li>
                            ))}
                        </ul>
                    </div>
                ) : null}

                {state.recipeData.instructions.length ? (
                    <div className={"instructions-wrapper"}>
                        <h3>Instructions</h3>
                        <ol className={"instructions"}>
                            {state.recipeData.instructions.map(data => (
                                <li className={"instruction"} key={data.key ?? data._id}>
                                    {data.instruction}
                                </li>
                            ))}
                        </ol>
                    </div>
                ) : null}
            </div>
            {!state.recipeData._id ? <Loader/> : null}
        </div>
    );
}

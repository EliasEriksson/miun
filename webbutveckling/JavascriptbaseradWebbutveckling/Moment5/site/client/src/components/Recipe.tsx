import React, {useState, useEffect} from "react";
import {Loader} from "./Loader";
import {requestEndpoint} from "../modules/requests";
import {Link, useParams} from "react-router-dom";

export type Unit = "ml" | "cl" | "dl" | "l" | "g" | "kg" | "st";

export interface IngredientData {
    _id: string
    ingredient: string,
}

export interface TagData {
    _id: string,
    tag: string
}

export interface RecipeIngredientData {
    _id: string,
    ingredient: IngredientData
    amount: number,
    unit: Unit
}

export interface RecipeTagData {
    _id: string,
    tag: TagData
}

export interface RecipeData {
    _id: string,
    title: string,
    description: string,
    ingredients: RecipeIngredientData[],
    instructions: string[],
    tags: RecipeTagData[]
}

export const RecipeSummary = (props: { data: RecipeData }) => {
    return (
        <div>
            <Link to={`/recipes/${props.data._id}`}><h2>{props.data.title}</h2></Link>
            <p>{props.data.description}</p>
        </div>
    );
}

export const Recipe = () => {
    const params = useParams();
    const [page, setPage] = useState<null | JSX.Element>(null);
    useEffect (() => {
        let mounted = true;
        requestEndpoint<RecipeData>(`/recipes/${params._id}`, "GET", null, undefined).then(async ([data, status]) => {
            if (200 <= status && status < 300 && mounted) {
                setPage(
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
            }
        })
        return () => {mounted = false}
    });

    return (
        <main>
            {page}
            {!page ? <Loader/> : null}
        </main>
    );
}

export const EditRecipe = () => {
    const params = useParams();
    return (
        <div>
            <p>this is the edit page!</p>
            <Link to={`/recipes/${params._id}`}>Go back</Link>
        </div>
    );
}
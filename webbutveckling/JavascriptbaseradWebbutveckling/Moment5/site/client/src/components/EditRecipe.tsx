import {Link, useParams} from "react-router-dom";
import React from "react";
import {RecipeForm} from "./RecipeForm";

export const EditRecipe = () => {
    const params = useParams();
    return (
        <div>
            <RecipeForm _id={`${params._id}`}/>
            <Link to={`/recipes/${params._id}`}>Go back</Link>
        </div>
    );
}
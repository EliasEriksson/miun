import {Link, useParams} from "react-router-dom";
import React from "react";
import {RecipeForm} from "./RecipeForm";

/**
 * renders the edit page of the site.
 */
export const EditRecipe = () => {
    const params = useParams();
    return (
        <div className={"content"}>
            <div className={"wrapper"}>
                <Link to={`/recipes/${params._id}`}>Go back</Link>
            </div>
            <RecipeForm _id={`${params._id}`}/>
        </div>
    );
}
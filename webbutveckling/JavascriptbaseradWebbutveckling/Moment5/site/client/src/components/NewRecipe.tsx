import React from "react";
import {RecipeForm} from "./RecipeForm";
import {Link} from "react-router-dom";

/**
 * renders the new recipe page
 *
 * @constructor
 */
export const NewRecipe = () => {
    return (
        <div className={"content"}>
            <div className={"wrapper"}>
                <Link to={`/`}>Go back</Link>
            </div>
            <RecipeForm />
        </div>
    )
}
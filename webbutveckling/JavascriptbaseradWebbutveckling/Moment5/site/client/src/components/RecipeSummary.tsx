import {RecipeData} from "../types";
import {Link} from "react-router-dom";
import React from "react";

export const RecipeSummary = (props: { data: RecipeData }) => {
    return (
        <div>
            <Link to={`/recipes/${props.data._id}`}><h2>{props.data.title}</h2></Link>
            <p>{props.data.description}</p>
        </div>
    );
}

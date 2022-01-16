import {RecipeData} from "../types";
import {Link} from "react-router-dom";
import React from "react";
import "../static/css/recipeSummary.scss"

/**
 * renders the title, description and the tags of a recipe
 *
 * @param props
 * @constructor
 */
export const RecipeSummary = (props: { data: RecipeData }) => {
    return (
        <div className={"summary"}>
            <h2><Link to={`/recipes/${props.data._id}`}>{props.data.title}</Link></h2>
            <p>{props.data.description}</p>
            <div className={"tags"}>
                {
                    props.data.tags.map(tagData => (
                        <div key={tagData.key ?? tagData._id} className={"tag"}>{tagData.tag.tag}</div>
                    ))
                }
            </div>
        </div>
    );
}

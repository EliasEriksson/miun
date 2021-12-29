import React from "react";
import {Loader} from "./Loader";
import {requestEndpoint} from "../modules/requests";

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

export class RecipeSummary extends React.Component<{ data: RecipeData }> {
    render = () => {
        return (
            <div>
                <a href={`/recipes/${this.props.data._id}`}><h2>{this.props.data.title}</h2></a>
                <p>{this.props.data.description}</p>
            </div>
        );
    }
}

export class Recipe extends React.Component<{
    _id: string
}, {
    page: JSX.Element | null
}> {
    constructor(props: { _id: string }) {
        super(props);
        this.state = {
            page: null
        }
    }

    async componentDidMount() {
        const [data, status] = await requestEndpoint<RecipeData>(`/recipes/${this.props._id}/`);
        if (200 <= status && status < 300) {
            this.setState(({
                page: (
                    <div>
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
                )
            }))
        }
    }

    render = () => {
        return (
            <main>
                {this.state.page}
                {!this.state.page ? <Loader/> : null}
            </main>
        );
    }
}
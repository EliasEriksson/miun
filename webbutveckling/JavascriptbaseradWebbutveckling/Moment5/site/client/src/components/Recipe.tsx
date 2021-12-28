import React from "react";

export type Unit = "ml" | "cl" | "dl" | "l" | "g" | "kg" | "st";

export interface Ingredient {
    _id: string
    ingredient: string,
}

export interface Tag {
    _id: string,
    tag: string
}

export interface RecipeData {
    title: string,
    description: string,
    ingredients: {
        ingredient: Ingredient
        amount: number,
        unit: Unit
    }[],
    instructions: string[],
    tags: Tag[]
}

export class RecipeSummary extends React.Component<RecipeData> {
    render = () => {
        return (
            <div>
                <h2>{this.props.title}</h2>
                <p>{this.props.description}</p>
            </div>
        );
    }
}

export class Recipe extends React.Component<RecipeData> {

}
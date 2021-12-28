import React from "react";

export type Unit = "ml" | "cl" | "dl" | "l" | "g" | "kg" | "st";

export interface IngredientData {
    _id: string
    ingredient: string,
}

export interface TagData {
    _id: string,
    tag: string
}

export interface RecipeData {
    _id: string,
    title: string,
    description: string,
    ingredients: {
        ingredient: IngredientData
        amount: number,
        unit: Unit
    }[],
    instructions: string[],
    tags: TagData[]
}

export class RecipeSummary extends React.Component<{data: RecipeData}> {
    render = () => {
        return (
            <div>
                <h2>{this.props.data.title}</h2>
                <p>{this.props.data.description}</p>
            </div>
        );
    }
}

export class Recipe extends React.Component<RecipeData> {

}
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
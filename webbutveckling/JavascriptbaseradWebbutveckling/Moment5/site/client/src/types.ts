export type Unit = "ml" | "cl" | "dl" | "l" | "g" | "kg" | "st";

type _ID = string

export interface IngredientData {
    _id?: _ID
    ingredient: string,
}

export interface TagData {
    _id?: _ID,
    tag: string
}

export interface RecipeIngredientData {
    _id?: _ID,
    ingredient: IngredientData
    amount: number,
    unit: Unit
}

export interface RecipeTagData {
    _id?: _ID,
    tag: TagData
}

export interface RecipeData {
    _id?: _ID,
    title: string,
    description: string,
    ingredients: RecipeIngredientData[],
    instructions: string[],
    tags: RecipeTagData[]
}

export interface RecipeRequestData {
    _id?: _ID,
    title: string,
    description: string,
    ingredients: {
        ingredient: _ID ,
        amount: number,
        unit: Unit
    }[],
    instructions: string[],
    tags: {
        tag: _ID,
    }[]

}

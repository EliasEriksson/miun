/**
 * this files contains interfaces for data returned from the api
 */

export type Unit = "ml" | "cl" | "dl" | "l" | "g" | "kg" | "st";
type _ID = string

export interface IngredientData {
    _id?: _ID;
    ingredient: string;
    key?: string;
}

export interface TagData {
    _id?: _ID;
    tag: string;
    key?: string;
}

export interface RecipeIngredientData {
    _id?: _ID;
    ingredient: IngredientData;
    amount: number;
    unit: Unit;
    key?: string;
}

export interface InstructionData {
    _id?: _ID;
    instruction: string;
    key?: string;
}

export interface RecipeTagData {
    _id?: _ID;
    tag: TagData;
    key?: string;
}

export interface RecipeData {
    _id?: _ID;
    title: string;
    description: string;
    ingredients: RecipeIngredientData[];
    instructions: InstructionData[];
    tags: RecipeTagData[];
    key?: string;
}

export interface RecipeRequestData {
    _id?: _ID;
    title: string;
    description: string;
    ingredients: {
        ingredient: _ID ,
        amount: number,
        unit: Unit
    }[];
    instructions: InstructionData[];
    tags: {
        tag: _ID,
    }[];
}

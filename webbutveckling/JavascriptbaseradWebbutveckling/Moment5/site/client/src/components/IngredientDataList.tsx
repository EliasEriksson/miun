import React, {useEffect, useState} from "react";
import {ApiResponse, requestEndpoint} from "../modules/requests";
import {IngredientData} from "../types";
import {v4 as uuid} from "uuid";


let mounted = false;

interface State {
    search: string,
    options: JSX.Element[]
}


export const IngredientDataList: React.FC<{
    initial: IngredientData, event: (data: IngredientData) => void
}> = props => {
    const [state, setState] = useState<State>({
        search: props.initial.ingredient,
        options: []
    });

    useEffect(() => {
        mounted = true;
        requestEndpoint<ApiResponse<IngredientData>>(`/ingredients/?s=${state.search}`).then(async (data) => {
            if (mounted) {
                state.options = data.docs.map(ingredientData =>
                    (
                        <option key={ingredientData.key ?? ingredientData._id} value={ingredientData.ingredient}/>
                    )
                );
                await setState({...state});
            }
        })

        return () => {
            mounted = false;
        };
    }, [state.search]);

    const identifier = props.initial.key ?? props.initial._id
    const htmlId = `${identifier}-ingredients`;
    return (
        <>
            <label htmlFor={identifier + "-ingredient-input"}>
                Ingredient:
                <datalist id={htmlId}>
                    {state.options}
                </datalist>
            </label>
            <input id={identifier + "-ingredient-input"} onChange={async (e) => {
                setState({...state, search: e.target.value})
            }} onBlur={async e => {
                setState({...state, search: e.target.value})
                const data = await requestEndpoint<ApiResponse<IngredientData>>(`/ingredients/?s=${e.target.value}&exact=true`);
                if (data.docs.length) {
                    props.event(data.docs[0]);
                } else {
                    props.event({
                        _id: "",
                        ingredient: e.target.value,
                        key: uuid()
                    });
                }

            }} list={htmlId} value={state.search}/>
        </>
    )
}
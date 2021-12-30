import React, {useEffect, useState} from "react";
import {ApiResponse, requestEndpoint} from "../modules/requests";
import {IngredientData} from "../types";

let mounted = false;


export const IngredientDataList = (props: { current: IngredientData, setIngredient: (data: IngredientData) => void }) => {
    const [search, setSearch] = useState(props.current.ingredient);
    const [options, setOptions] = useState<JSX.Element[]>([]);

    useEffect(() => {
        mounted = true;
        requestEndpoint<ApiResponse<IngredientData>>(`/ingredients/?s=${search}`).then(async ([data, status]) => {
            if (200 <= status && status < 300 && mounted) {
                await setOptions(data.docs.map(ingredientData => {
                    return (<option key={ingredientData._id} value={ingredientData.ingredient}/>);
                }));
            }
        })

        return () => {
            mounted = false;
        };
    }, [search]);

    const htmlId = `${props.current._id}-tags`;
    return (
        <label>
            <input onChange={async (e) => {
                await setSearch(e.target.value);
            }} onBlur={async e => {
                await setSearch(e.target.value);
                const [data, status] = await requestEndpoint<ApiResponse<IngredientData>>(`/ingredients/?s=${search}&exact=true`);
                if (200 <= status && status < 300 && data.docs.length) {
                    props.setIngredient(data.docs[0]);
                }
            }} list={htmlId} value={search}/>
            <datalist id={htmlId}>
                {options}
            </datalist>
        </label>
    )
}
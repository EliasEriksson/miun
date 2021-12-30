import React, {useEffect, useState} from "react";
import {ApiResponse, requestEndpoint} from "../modules/requests";
import {IngredientData} from "../types";

let mounted = false;


export const IngredientDataList = (props: { initial: IngredientData, event: (data: IngredientData) => void }) => {
    const [search, setSearch] = useState(props.initial.ingredient);
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

    const htmlId = `${props.initial._id}-tags`;
    return (
        <label>
            <input onChange={async (e) => {
                setSearch(e.target.value);
            }} onBlur={async e => {
                setSearch(e.target.value);
                const [data, status] = await requestEndpoint<ApiResponse<IngredientData>>(`/ingredients/?s=${e.target.value}&exact=true`);
                if (200 <= status && status < 300 && data.docs.length) {
                    props.event(data.docs[0]);
                }
            }} list={htmlId} value={search}/>
            <datalist id={htmlId}>
                {options}
            </datalist>
        </label>
    )
}
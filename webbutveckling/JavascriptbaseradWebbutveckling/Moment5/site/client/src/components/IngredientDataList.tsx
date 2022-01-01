import React, {useEffect, useState} from "react";
import {ApiResponse, requestEndpoint} from "../modules/requests";
import {IngredientData} from "../types";

let mounted = false;


export const IngredientDataList = (props: { initial: IngredientData, event: (data: IngredientData) => void }) => {
    const [search, setSearch] = useState(props.initial.ingredient);
    const [options, setOptions] = useState<JSX.Element[]>([]);

    useEffect(() => {
        mounted = true;
        requestEndpoint<ApiResponse<IngredientData>>(`/ingredients/?s=${search}`).then(async (data) => {
            if (mounted) {
                await setOptions(data.docs.map(ingredientData => {
                    return (<option key={ingredientData._id} value={ingredientData.ingredient}/>);
                }));
            }
        })

        return () => {
            mounted = false;
        };
    }, [search]);

    const htmlId = `${props.initial._id}-ingredients`;
    return (
        <label>
            <input onChange={async (e) => {
                setSearch(e.target.value);
            }} onBlur={async e => {
                setSearch(e.target.value);
                const data = await requestEndpoint<ApiResponse<IngredientData>>(`/ingredients/?s=${e.target.value}&exact=true`);
                if (data.docs.length) {
                    props.event(data.docs[0]);
                } else {
                    props.event({
                        _id: "",
                        ingredient: e.target.value
                    });
                }

            }} list={htmlId} value={search}/>
            <datalist id={htmlId}>
                {options}
            </datalist>
        </label>
    )
}
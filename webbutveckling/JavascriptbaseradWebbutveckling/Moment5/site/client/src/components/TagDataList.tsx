import React, {useEffect, useState} from "react";
import {ApiResponse, requestEndpoint} from "../modules/requests";
import {TagData} from "../types";
import {v4 as uuid} from "uuid";

/**
 * should be a ref but cba to change it
 */
let mounted = false;

interface State {
    search: string,
    options: JSX.Element[]
}

/**
 * component for the tag data list in the recipe form
 *
 * when the user starts typing in the text input a request is sent to
 * /api/tags/ to preform a search. 5 search results comes back and is inserted
 * in the input fields related datalist to provide the user with autocomplete.
 */
export const TagDataList = (props: { initial: TagData, event: (data: TagData) => void }) => {
    const [state, setState] = useState<State>({
        search: props.initial.tag,
        options: []
    })

    useEffect(() => {
        mounted = true;
        requestEndpoint<ApiResponse<TagData>>(`/tags/?s=${state.search}`).then(async (data) => {
            if (mounted) {
                state.options = data.docs.map(tagData => {
                    return (<option key={tagData.key ?? tagData._id} value={tagData.tag}/>);
                });
                await setState({...state});
            }
        })

        return () => {
            mounted = false;
        };
    }, [state.search]);

    const identifier = props.initial.key ?? props.initial._id
    const htmlId = `${identifier}-tags`;
    return (
        <>
            <label htmlFor={`${identifier}-input`}>Tag:</label>
            <input id={`${identifier}-input`} onChange={async (e) => {
                await setState({...state, search: e.target.value.replace(/[^\w-]/gu, "")});
            }} onBlur={async e => {
                await setState({...state, search: e.target.value});

                const data = await requestEndpoint<ApiResponse<TagData>>(`/tags/?s=${e.target.value}&exact=true`);
                if (data.docs.length) {
                    props.event(data.docs[0]);
                } else {
                    props.event({
                        _id: "",
                        "tag": e.target.value,
                        key: uuid()
                    })
                }
            }} onKeyPress={e => {
                if (e.key === "Enter") e.preventDefault()
            }} list={htmlId} value={state.search}/>
            <datalist id={htmlId}>
                {state.options}
            </datalist>
        </>
    )
}
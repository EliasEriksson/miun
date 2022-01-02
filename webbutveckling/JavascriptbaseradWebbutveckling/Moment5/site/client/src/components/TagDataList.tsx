import React, {useEffect, useState} from "react";
import {ApiResponse, requestEndpoint} from "../modules/requests";
import {TagData} from "../types";


let mounted = false;

interface State {
    search: string,
    options: JSX.Element[]
}


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
                    return (<option key={tagData._id} value={tagData.tag}/>);
                });
                await setState({...state});
            }
        })

        return () => {
            mounted = false;
        };
    }, [state.search]);

    const htmlId = `${props.initial._id}-tags`;
    return (
        <label>
            <input onChange={async (e) => {
                setState({...state, search: e.target.value});
            }} onBlur={async e => {
                setState({...state, search: e.target.value});

                const data = await requestEndpoint<ApiResponse<TagData>>(`/tags/?s=${e.target.value}&exact=true`);
                if (data.docs.length) {
                    props.event(data.docs[0]);
                } else {
                    props.event({
                        _id: "",
                        "tag": e.target.value
                    })
                }
            }} list={htmlId} value={state.search}/>
            <datalist id={htmlId}>
                {state.options}
            </datalist>
        </label>
    )
}
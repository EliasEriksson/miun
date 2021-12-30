import React, {useEffect, useState} from "react";
import {ApiResponse, requestEndpoint} from "../modules/requests";
import {TagData} from "../types";


let mounted = false;


export const TagDataList = (props: { _id: string, setTag: (data: TagData) => void }) => {
    const [search, setSearch] = useState("");
    const [options, setOptions] = useState<JSX.Element[]>([]);

    useEffect(() => {
        mounted = true;
        requestEndpoint<ApiResponse<TagData>>(`/tags/?s=${search}`).then(async ([data, status]) => {
            if (200 <= status && status < 300 && mounted) {
                await setOptions(data.docs.map(tagData => {
                    return (<option key={tagData._id} value={tagData.tag}/>);
                }));
            }
        })

        return () => {
            mounted = false;
        };
    }, [search]);

    const htmlId = `${props._id}-tags`
    return (
        <label>
            <input onChange={async (e) => {
                await setSearch(e.target.value);
            }} onBlur={async e => {
                const [data, status] = await requestEndpoint<ApiResponse<TagData>>(`/tags/?s=${search}&exact=true`);
                if (200 <= status && status < 300 && data.docs.length) {
                    props.setTag(data.docs[0]);
                }
            }} list={htmlId} value={search}/>
            <datalist id={htmlId}>
                {options}
            </datalist>
        </label>
    )
}
import React from "react";
import loading from "../loading.svg";
import handleViewport from "react-in-viewport";


export const Loader = handleViewport((props: { inViewport: boolean, forwardedRef: React.Ref<any> }) => {
    return (
        <img ref={props.forwardedRef} src={loading} alt={"loading more content"}/>
    );
});

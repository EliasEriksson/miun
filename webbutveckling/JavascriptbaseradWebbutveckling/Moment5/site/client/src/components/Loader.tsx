import React from "react";
import loading from "../loading.svg";
import handleViewport from "react-in-viewport";

/**
 * a loader component that knows if its in the current viewport or not
 */
export const Loader = handleViewport((props: { forwardedRef: React.Ref<any> }) => {
    return (
        <img className={"loader"} ref={props.forwardedRef} src={loading} alt={"loading more content"}/>
    );
});

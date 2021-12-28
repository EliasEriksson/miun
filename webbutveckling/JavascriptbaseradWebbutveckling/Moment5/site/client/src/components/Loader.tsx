import React from "react";
import loading from "../loading.svg";
import handleViewport from "react-in-viewport";


class InnerLoader extends React.Component<{forwardedRef: React.Ref<any>}> {
    render = () => {
        return (<img ref={this.props.forwardedRef} src={loading} alt={"loading more content"}/>);
    }
}

export const Loader = handleViewport(InnerLoader);
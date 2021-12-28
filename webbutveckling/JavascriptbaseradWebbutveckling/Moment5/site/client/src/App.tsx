import React from 'react';
import {Home} from "./components/Home";


export class App extends React.Component<{}, { page: string }> {
    constructor(props: {}) {
        super(props);

        this.state = {
            page: "home"
        }
    }

    render = () => {
        let page: JSX.Element;
        switch (this.state.page) {
            case "home":
                page = <Home/>;
                break;
            default:
                page = <Home/>;
        }
        return (
            page
        );
    }
}
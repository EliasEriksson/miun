import React from "react";
import {ReactComponent as Logo} from "../media/assests/logo.svg";
import "../static/css/header.scss";
import "../static/css/footer.scss";
import {Link} from "react-router-dom";

/**
 * the sites header
 */
export class Header extends React.Component<any, any> {
    render = () => {
        return (
            <div className={"header-wrapper"}>
                <header>
                    <Link to={"/"}>
                        <Logo/>
                    </Link>
                    <h1>Cooking Anarchy</h1>
                </header>
            </div>
        );
    }
}

/**
 * the sites footer
 */
export class Footer extends React.Component<any, any> {
    render = () => {
        return (
            <div className={"footer-wrapper"}>
                <footer>
                    <p>Skapad av Elias Eriksson 🄯</p>
                    <a href={"https://github.com/EliasEriksson/miun/tree/master/webbutveckling/JavascriptbaseradWebbutveckling/Moment5/site"}
                    >repo</a>
                </footer>
            </div>
        )
    }
}
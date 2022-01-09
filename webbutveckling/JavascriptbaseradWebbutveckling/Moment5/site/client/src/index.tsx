import React from 'react';
import ReactDOM from 'react-dom';
import {HashRouter, Route, Routes} from "react-router-dom";
import './static/css/index.scss';
import reportWebVitals from './reportWebVitals';
import {Footer, Header} from "./components/margins";
import {Home} from "./components/Home";
import {ViewRecipe} from "./components/ViewRecipe";
import {EditRecipe} from "./components/EditRecipe";
import {NewRecipe} from "./components/NewRecipe";

/**
 * the root of the application.
 *
 * defines the paths for the sites pages.
 */
ReactDOM.render(
    <React.StrictMode>
        <HashRouter>
            <div className={"wrapper top-content"}>
                <Header/>
                <main className={"content-wrapper"}>
                    <Routes>
                        <Route path={"/"} element={<Home/>}/>
                        <Route path={"/recipes/:_id/"} element={<ViewRecipe/>}/>
                        <Route path={"/recipes/edit/:_id/"} element={<EditRecipe/>}/>
                        <Route path={"/recipes/new/"} element={<NewRecipe/>}/>
                    </Routes>
                </main>
            </div>
            <Footer/>
        </HashRouter>
    </React.StrictMode>,
    document.getElementById('root')
);

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();

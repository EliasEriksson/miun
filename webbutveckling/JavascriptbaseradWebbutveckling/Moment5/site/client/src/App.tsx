import React from 'react';
import {Routes, Route, Router, HashRouter} from "react-router-dom";
import {Home} from "./components/Home";
import {Footer, Header} from "./components/margins";
import {ViewRecipe} from "./components/ViewRecipe";
import {EditRecipe} from "./components/EditRecipe";
import {NewRecipe} from "./components/NewRecipe";


export const App = () => {
    return (
        <>
            <div className={"wrapper top-content"}>
                <Header/>
                <main className={"content-wrapper"}>
                    <Routes>
                        <Route path={"/"} element={<Home/>}/>
                        <Route path={"/recipes/:_id/"} element={<ViewRecipe/>}/>
                        <Route path={"/recipes/edit/:_id/"} element={<EditRecipe/>}/>
                        <Route path={"recipes/new/"} element={<NewRecipe/>}/>
                    </Routes>
                </main>
            </div>
            <Footer/>
        </>
    );
}
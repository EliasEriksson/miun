import React from 'react';
import {Routes, Route} from "react-router-dom";
import {Home} from "./components/Home";
import {Footer, Header} from "./components/margins";
import {EditRecipe, Recipe} from "./components/Recipe";


export const App = () => {
    return (
        <>
            <Header/>
            <Routes>
                <Route path={"/"} element={<Home/>}/>
                <Route path={"/recipes/:_id"} element={<Recipe/>}/>
                <Route path={"/recipes/edit/:_id"} element={<EditRecipe/>}/>
            </Routes>
            <Footer/>
        </>
    );
}
import React, {SetStateAction, useEffect, useState} from "react";
import {ApiResponse, requestEndpoint} from "../modules/requests";
import {Loader} from "./Loader";
import {RecipeData} from "../types";
import {RecipeSummary} from "./RecipeSummary";


let fetching = false;
let fetchMore = true;
let nextApiPage: number | null = 1;


interface State {
    recipes: JSX.Element[],
    fetchedAll: boolean
}

const fetchContent = async (state: State, setState: React.Dispatch<SetStateAction<State>>) => {
    fetchMore = true;
    while (fetchMore && nextApiPage) {
        fetching = true;
        if (nextApiPage) {
            const data = await requestEndpoint<ApiResponse<RecipeData>>(
                `/recipes/?page=${nextApiPage}`
            );
            data.docs.forEach(recipeData => {
                state.recipes.push((
                    <RecipeSummary key={recipeData._id} data={recipeData}/>
                ))
            })
            await setState({
                ...state,
            })
            nextApiPage = data.nextPage;
            if (!nextApiPage) await setState({...state, fetchedAll: true});
            fetching = false;
        }
    }
}

export const Home = () => {
    const [state, setState] = useState<State>({
        recipes: [],
        fetchedAll: false
    });

    useEffect(() => {
        return () => {
            fetching = false;
            fetchMore = true
            nextApiPage = 1;
            setState({recipes: [], fetchedAll: false})
        };
    }, []);

    return (
        <main>
            <div>{state.recipes}</div>
            {!state.fetchedAll ? <Loader
                onEnterViewport={async () => fetchContent(state, setState)}
                onLeaveViewport={async () => fetchMore = false}/> : null}
        </main>
    )
}

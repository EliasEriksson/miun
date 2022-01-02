import React, {useState} from "react";
import {ApiResponse, requestEndpoint} from "../modules/requests";
import {Loader} from "./Loader";
import {RecipeData} from "../types";
import {RecipeSummary} from "./RecipeSummary";


let fetching = false;
let fetchMore = true;
let nextApiPage: number | null = 1;


export const Home = () => {
    const [state, setState] = useState<{ recipes: JSX.Element[], fetchedAll: boolean }>({
        recipes: [],
        fetchedAll: false
    });

    return (
        <main>
            <div>{state.recipes}</div>
            {!state.fetchedAll ? <Loader
                onEnterViewport={async () => {
                    fetchMore = true;
                    while (fetchMore && nextApiPage) {
                        fetching = true;
                        if (nextApiPage) {
                            const data = await requestEndpoint<ApiResponse<RecipeData>>(
                                `/recipes/?page=${nextApiPage}`
                            );
                            data.docs.forEach(recipeData => {
                                state.recipes.push((
                                    <RecipeSummary key={recipeData._id} data={recipeData} />
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
                }}
                onLeaveViewport={async () => {
                        fetchMore = false;
                }}/> : null}
        </main>
    )
}

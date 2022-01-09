import React, {SetStateAction, useEffect, useState} from "react";
import {ApiResponse, requestEndpoint} from "../modules/requests";
import {Loader} from "./Loader";
import {RecipeData} from "../types";
import {RecipeSummary} from "./RecipeSummary";
import {Link} from "react-router-dom";
import "../static/css/home.scss";

/**
 * these should be refs but cba to change them now
 */
let fetching = false;
let fetchMore = true;
let nextApiPage: number | null = 1;
let recipeIds: Set<string> = new Set();


interface State {
    search: string
    recipes: RecipeData[],
    fetchedAll: boolean
}

/**
 * preforms a full reset on the state and global variables.
 */
const reset = async (state: State, setState: React.Dispatch<SetStateAction<State>>) => {
    fetching = false;
    fetchMore = true;
    nextApiPage = 1;
    recipeIds = new Set<string>();
    state.recipes = [];
    state.fetchedAll = false;
    await setState({...state});
}

/**
 * fetches more content as long as its needed / possible.
 *
 * fetches content until fetch more is set to false
 * elsewhere in the code or until there is no next api page
 */
const fetchContentS = async (state: State, setState: React.Dispatch<SetStateAction<State>>) => {
    fetchMore = true;

    while (fetchMore && nextApiPage) {
        fetching = true;

        // if search is set preform a search
        if (state.search) {
            const data = await requestEndpoint<{
                relevantSearch: ApiResponse<RecipeData> | null,
                titleSearch: ApiResponse<RecipeData> | null,
                ingredientSearch: ApiResponse<RecipeData> | null
            }>(`/recipes/?page=${nextApiPage}&s=${encodeURIComponent(state.search)}`);
            if (data.relevantSearch) {
                data.relevantSearch.docs.forEach(recipeData => {
                    if (!recipeIds.has(recipeData._id as string)) {
                        state.recipes.push(recipeData);
                        recipeIds.add(recipeData._id as string);
                    }
                });
            }
            if (data.titleSearch) {
                data.titleSearch.docs.forEach(recipeData => {
                    if (!recipeIds.has(recipeData._id as string)) {
                        state.recipes.push(recipeData);
                        recipeIds.add(recipeData._id as string);
                    }
                });
            }
            if (data.ingredientSearch) {
                data.ingredientSearch.docs.forEach(recipeData => {
                    if (!recipeIds.has(recipeData._id as string)) {
                        state.recipes.push(recipeData);
                        recipeIds.add(recipeData._id as string);
                    }
                });
            }

            if (data.relevantSearch?.hasNextPage || data.titleSearch?.hasNextPage || data.ingredientSearch?.hasNextPage) {
                nextApiPage++;
            } else {
                nextApiPage = null;
            }

        } else {
            const data = await requestEndpoint<ApiResponse<RecipeData>>(
                `/recipes/?page=${nextApiPage}`
            );
            data.docs.forEach(recipeData => {
                // id is guaranteed on objects directly from db
                if (!recipeIds.has(recipeData._id as string)) {
                    state.recipes.push(recipeData);
                    recipeIds.add(recipeData._id as string);
                }
            });
            nextApiPage = data.nextPage;
        }
        if (!nextApiPage) {
            await setState({...state, fetchedAll: true});
        } else {
            await setState({...state});
        }
        fetching = false;
    }
}

/**
 * the home page.
 */
export const Home: React.FC = () => {
    const [state, setState] = useState<State>({
        search: "",
        recipes: [],
        fetchedAll: false
    });


    /**
     * reset all variables if the pages is changed.
     */
    useEffect(() => {
        return () => {
            reset(state, setState);
        };
    }, []);

    return (
        <>
            <div className={"home"}>
                <div className={"controls"}>
                    <form onSubmit={async e => e.preventDefault()}>
                        <label>
                            Search:
                            <input onChange={async e => {
                                state.search = e.target.value;
                                while (fetching) {
                                    await new Promise(resolve => setTimeout(resolve, 40));
                                }
                                await reset(state, setState);

                            }} value={state.search} placeholder={"cake #fast"}/>
                        </label>
                    </form>
                    <div className={"new"}>
                        <Link to={"/recipes/new/"}>Create new recipe</Link>
                    </div>
                </div>
                <div>
                    {
                        state.recipes.map(recipeData => (
                            <React.Fragment key={recipeData._id}>
                                <hr/>
                                <RecipeSummary data={recipeData}/>
                            </React.Fragment>
                        ))
                    }
                </div>
                <hr/>
                {!state.fetchedAll ? <Loader
                    onEnterViewport={async () => fetchContentS(state, setState)}
                    onLeaveViewport={async () => fetchMore = false}/> : null}
            </div>
        </>
    )
}
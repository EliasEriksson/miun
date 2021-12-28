import {getCookies} from "./cookie";


type Endpoints = `/ingredients/${string}` |`/tags/${string}` | `/recipes/${string}`


type HttpMethods = "GET" | "POST" | "PUT" | "DELETE";

interface ApiResponse<T> {
    docs: T[],
    totalDocs: number,
    limit: number,
    totalPages: number,
    page: number,
    pagingCounter: number,
    hasPrevPage: boolean,
    hasNextPage: number | null,
    prevPage: number | null,
    nextPage: number | null
}

/**
 * a general function to preform GET / POST / PUT / DELETE request.
 *
 * necessary headers will be set as needed.
 *
 * @param endpoint: the api endpoint to request
 * @param token: the authentication token. required for POST / PUT / DELETE.
 * @param method: the request method.
 * @param data: general data to be sent with the request.
 */
export const requestEndpoint = async <T>(endpoint: Endpoints, method: HttpMethods = "GET", token = null, data = undefined): Promise<[ApiResponse<T>, number]> => {
    const apiURL = getCookies()["apiRoot"];

    let headers = new Headers({
        "Content-Type": "application/json"
    });
    if (token) {
        headers.set("Authorization", `Token ${token}`);
    }

    let init: RequestInit = {
        method: method,
        headers: headers
    };

    if (data) {
        init["body"] = JSON.stringify(data);
    }
    let response = await fetch(`${apiURL}${endpoint}`, init);
    return [await response.json(), response.status];
};
// noinspection TypeScriptDuplicateUnionOrIntersectionType
export type Endpoints = `/ingredients/${string}` |`/tags/${string}` | `/recipes/${string}`

export type HttpMethods = "GET" | "POST" | "PUT" | "DELETE";

export interface ApiResponse<T> {
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
 * @param token: the authentication token.
 * @param method: the request method.
 * @param data: general data to be sent with the request.
 */
export const requestEndpoint = async <T>(endpoint: Endpoints, method: HttpMethods = "GET", token = null, data: object|undefined = undefined): Promise<T> => {
    const apiURL = `/jsweb/moment5/api`

    let headers = new Headers({
        "Content-Type": "application/json"
    });

    if (token) {
        headers.set("Authorization", `Token ${token}`);
    }

    let init: RequestInit = {
        method: method,
        headers: headers,
    };

    if (data) {
        init["body"] = JSON.stringify(data);
    }
    const url = `${apiURL}${endpoint}`;
    let response = await fetch(url, init);
    if (200 <= response.status && response.status < 300) {
        return await response.json();
    }
    throw new Error(await response.text());
};
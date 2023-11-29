import http from '@/services/http.js';

const AUTH_TOKEN_NAME='laravue_app_token';

async function attempt(email, password)
{
    try
    {
        const request = await http.post('/login', {
            email,
            password
        });

        return request;
    }
    catch (error)
    {
        if (error?.response?.data) {
            return error.response.data;
        }

        return false;
    }
}

async function checkAuth()
{
    try
    {
        if (!existsAuthToken()) {
            return false;
        }

        const request = await http.get('/user', {
            headers: {
                Authorization: 'Bearer '.concat(getToken())
            }
        });

        const { data } = request;

        if (!data?.success) {
            return false;
        }

        return data?.data ?? false;
    }
    catch (error)
    {
        if (error?.response?.status === 401) {
            return false;
        }

        return false;
    }
}

/**
 * Metodo responsavel por verificar se o token existe no localStorage (Nao verifica a validade dele)
 * @return {boolean}
*/
function existsAuthToken()
{
    return localStorage[AUTH_TOKEN_NAME] !== undefined;
}

/**
 * Retorna o token salvo no local storage
 * @return {string} O token salvo no localStorage
*/
function getToken()
{
    return localStorage[AUTH_TOKEN_NAME];
}

function setToken(token)
{
    localStorage[AUTH_TOKEN_NAME] = token;
}

function removeToken()
{
    localStorage.removeItem(AUTH_TOKEN_NAME);
}

function logOut()
{
    console.log('logOut');
}

export {
    attempt,
    existsAuthToken,
    getToken,
    checkAuth,
    logOut,
    setToken,
    removeToken
};
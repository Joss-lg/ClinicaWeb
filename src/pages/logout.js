export const GET = ({ cookies, redirect }) => {
  cookies.delete('user_session', { path: '/' });
  return redirect('/login');
};
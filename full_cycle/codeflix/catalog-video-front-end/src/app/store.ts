import { Action, combineReducers, configureStore, PreloadedState, ThunkAction } from '@reduxjs/toolkit';
import { apiSlice } from '../features/api/apiSlice';
import { castMembersApiSlice } from '../features/castMembers/castMemberSlice';
import { categoriesApiSlice } from '../features/categories/categorySlice';
import counterReducer from '../features/counter/counterSlice';
import { genreSlice } from '../features/genres/genreSlice';
import { videoSlice } from '../features/videos/videoSlice';
import { uploadReducer } from "../features/uploads/UploadSlice";
import { uploadQueue } from '../middlewares/uploadQueue';

const rootReducer = combineReducers({
  counter: counterReducer,
  [apiSlice.reducerPath]: apiSlice.reducer,
  [categoriesApiSlice.reducerPath]: apiSlice.reducer,
  [castMembersApiSlice.reducerPath]: apiSlice.reducer,
  [videoSlice.reducerPath]: apiSlice.reducer,
  [genreSlice.reducerPath]: apiSlice.reducer,
  uploadSlice: uploadReducer,
});

export const setupStore = (preloadedState?: PreloadedState<RootState>) => {
  return configureStore({
    reducer: rootReducer,
    preloadedState,
    middleware: (getDefaultMiddleware) =>
      getDefaultMiddleware({
        serializableCheck: false
      })
      .prepend(uploadQueue.middleware)
      .concat(apiSlice.middleware),
  });
};

export type AppStore = ReturnType<typeof setupStore>;
export type AppDispatch = AppStore['dispatch'];
export type RootState = ReturnType<typeof rootReducer>;

export type AppThunk<ReturnType = void> = ThunkAction<
  ReturnType,
  RootState,
  unknown,
  Action<string>
>;

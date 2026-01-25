from contextlib import asynccontextmanager
from fastapi.responses import RedirectResponse
from fastapi import FastAPI, Form
from pydantic import EmailStr
from sqlmodel import SQLModel, Field, Session, create_engine
from starlette.status import HTTP_303_SEE_OTHER


class Subscription(SQLModel, table=True):
    id: int | None = Field(default=None, primary_key=True)
    email: EmailStr = Field()


engine = create_engine("sqlite:///subscriptions.db")


@asynccontextmanager
async def lifespan(app: FastAPI):
    SQLModel.metadata.create_all(engine)
    yield


app = FastAPI(lifespan=lifespan)


@app.post("/subscribe")
def subscribe(email: EmailStr = Form(...)):
    with Session(engine) as session:
        session.add(Subscription(email=email.strip().lower()))
        session.commit()
    return RedirectResponse(url="https://beta.alvarezrosa.com/subscription", status_code=HTTP_303_SEE_OTHER)

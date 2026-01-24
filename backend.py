from contextlib import asynccontextmanager
from fastapi import FastAPI
from pydantic import EmailStr
from sqlmodel import SQLModel, Field, Session, create_engine


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
def subscribe(email: EmailStr):
    with Session(engine) as session:
        session.add(Subscription(email=email.strip().lower()))
        session.commit()
